<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Validator;
use Str;
use Elliptic\EC;
use kornrunner\Keccak;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;

class ApiController extends Controller
{
    public function createWallet(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                'wallet_name' => 'required|string', 
                'existing_wallet_address' => 'required|in:yes,no',
                'import_by' => 'nullable|in:private_key,mnemonic',
                'mnemonic' => 'nullable|string',
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

	        if($request->existing_wallet_address == 'yes' && $request->import_by == 'private_key'){
	        	if(empty($request->private_key)){
	        		return response()->json(['status'=>false, 'message'=>'Private key field is required', 'wallet'=>new \stdClass()],422);
	        	}

	        	$private_key = $request->private_key;

	        	$scriptPath = public_path('web3/erc20GetWalletAddressPKey.js');

				// Pass private key as argument
				$command = "node " . escapeshellarg($scriptPath) . " " . escapeshellarg($private_key);

				$output = shell_exec($command);

				$data = json_decode($output, true);

				if(!$data['status']){
					return response()->json(['status'=>false, 'message'=>'Invalid Private Key', 'wallet'=>new \stdClass()],400);
				}

				$wallet_address = $data['address'];

				$private_key = Crypt::encryptString($private_key);



	        }elseif($request->existing_wallet_address == 'yes' && $request->import_by == 'mnemonic'){
	        	//$private_key = "mnemonic"; 

	        	if(empty($request->mnemonic)){
	        		return response()->json(['status'=>false, 'message'=>'mnemonic field is required', 'wallet'=>new \stdClass()],422);
	        	}

	        	$mnemonic = $request->mnemonic;

				$scriptPath = public_path('web3/erc20GetWalletFromMnemonic.js');

				$command = "node " . escapeshellarg($scriptPath) . " " . escapeshellarg($mnemonic);

				$output = shell_exec($command);

				$wallet = json_decode($output, true);

				if(!$wallet['status']){
					return response()->json(['status'=>false, 'message'=>'Something Went Wrong', 'wallet'=>new \stdClass()],400);
				}

				$wallet_address = $wallet['address'];

				$private_key = Crypt::encryptString($wallet['privateKey']);

	        }else if($request->existing_wallet_address == 'no' && !isset($request->import_by) && !isset($request->private_key)){

	        	$scriptPath = public_path('web3/erc20WalletGenerate.js');
	            $command = "node " . escapeshellarg($scriptPath);
				$output = shell_exec($command);

				$wallet = json_decode($output, true);


				if(!$wallet){
					return response()->json(['status'=>false, 'message'=>'Something Went Wrong', 'wallet'=>new \stdClass()],400);
				}

				$wallet_address = $wallet['address'];

				$private_key = Crypt::encryptString($wallet['privateKey']);

				$phrase = Crypt::encryptString($wallet['mnemonic']);

	        }else{
	        	return response()->json(['status'=>false, 'message'=>'Something Went Wrong', 'wallet'=>new \stdClass()],400);
	        }

	        $getWallet = Wallet::where('wallet_address',$wallet_address)->first();

	        if($getWallet){
	        	return response()->json(['status'=>false, 'message'=>'Already the wallet address has been taken', 'wallet'=>new \stdClass()],422);
	        }

            $wallet = Wallet::create([
            	'wallet_name' => $request->wallet_name,
            	'existing_wallet_address' => $request->existing_wallet_address,
            	'import_by' => $request->import_by,
            	'wallet_type' => $request->wallet_type,
            	'private_key' => $private_key,
            	'mnemonic' => isset($phrase)?$phrase:$request->mnemonic,
            	'wallet_address' => $wallet_address,
            ]);

            return response()->json(['status'=>true, 'message'=>'Successfully a wallet created', 'wallet'=>$wallet]);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }
}
