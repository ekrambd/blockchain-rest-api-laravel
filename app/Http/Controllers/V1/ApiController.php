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
            	'mnemonic' => isset($phrase)?$phrase:Crypt::encryptString($request->mnemonic),
            	'wallet_address' => $wallet_address,
            ]);

            return response()->json(['status'=>true, 'message'=>'Successfully a wallet created', 'wallet'=>$wallet]);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function wallets(Request $request)
	{
	    try {
	        $query = Wallet::query();

	        $search = $request->search;

	        if ($request->filled('search')) {
	            $search = $request->search;
	            $query->where(function ($q) use ($search) {
	                $q->where('wallet_name', 'LIKE', "%{$search}%")
	                  ->orWhere('wallet_address', 'LIKE', "%{$search}%");
	            });
	        }

	        if($request->filled('sort_by')){
	        	$sortBy = $request->sort_by;
	        	if($sortBy == 'desc'){
	        		$query->latest();
	        	}
	        }

	        $hasPaginate = $request->get('has_paginate', 0);

	        if ($hasPaginate == 1) {
	            $perPage = $request->get('per_page', 10);
	            $wallets = $query->paginate($perPage);
	            return response()->json($wallets);
	        } else {
	            $wallets = $query->get();
	            return response()->json([
	                'status' => true,
	                'total'  => $wallets->count(),
	                'data'   => $wallets
	            ]);
	        }

	    } catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function deleteWallet($id)
	{
		try
		{
			$wallet = Wallet::findorfail($id);
			$wallet->delete();
			return response()->json(['status'=>true, 'message'=>"Successfully the wallet has been deleted"]);
		}catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function bnbBalance(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
                'wallet_address' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $walletAddress = $request->wallet_address;

            $scriptPath = public_path('web3/bnbBalance.js');

			$command = "node " . escapeshellarg($scriptPath) . " " . escapeshellarg($walletAddress);

			$output = trim(shell_exec($command));

			if($output == null){
				return response()->json(['status'=>false, 'message'=>'Something Went Wrong', 'data'=>new \stdClass()],404);
			}

			$data = array('currency'=>'Binance Smart Chain', 'symbol'=>'BNB', 'balance'=>strval($output));

			return response()->json(['status'=>true, 'message'=>'Record Found', 'data'=>$data]);

		}catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function polygonBalance(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
                'wallet_address' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $walletAddress = $request->wallet_address;

            $scriptPath = public_path('web3/polBalance.js');

			// Pass private key as argument
			$command = "node " . escapeshellarg($scriptPath) . " " . escapeshellarg($walletAddress);

			$output = trim(shell_exec($command));


			if($output == null){
				return response()->json(['status'=>false, 'message'=>'Something Went Wrong', 'data'=>new \stdClass()],404);
			}

			$data = array('currency'=>'Polygon', 'symbol'=>'POL', 'balance'=>strval($output));

			return response()->json(['status'=>true, 'message'=>'Record found', 'data'=>$data]);

		}catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function ethereumBalance(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
                'wallet_address' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $walletAddress = $request->wallet_address;

            $scriptPath = public_path('web3/ethBalance.js');

			// Pass private key as argument
			$command = "node " . escapeshellarg($scriptPath) . " " . escapeshellarg($walletAddress);

			$output = trim(shell_exec($command));


			if($output == null){
				return response()->json(['status'=>false, 'message'=>'Something Went Wrong', 'data'=>new \stdClass()],404);
			}

			$data = array('currency'=>'Ethereum', 'symbol'=>'ETH', 'balance'=>strval($output));

			return response()->json(['status'=>true, 'message'=>'Record found', 'data'=>$data]);
		}catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function walletInfoLock(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
                'wallet_id' => 'required|integer|exists:wallets,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $wallet = Wallet::findorfail($request->wallet_id);

            $privateKey = Crypt::decryptString($wallet->private_key);
            $mnemonic = Crypt::decryptString($wallet->mnemonic);

            $data = array('id'=>$wallet->id, 'wallet_address'=>$wallet->wallet_address, 'private_key'=>$privateKey, 'mnemonic'=>$mnemonic);

            return response()->json(['status'=>true, 'data'=>$data]);

		}catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function maxRangeBNB(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
			    'wallet_id'   => 'nullable|integer|exists:wallets,id|required_without_all:private_key,mnemonic',
			    'private_key' => 'nullable|string|required_without_all:wallet_id,mnemonic',
			    'mnemonic'    => 'nullable|string|required_without_all:wallet_id,private_key',
			]);

			if ($validator->fails()) {
			    return response()->json([
			        'status' => false,
			        'message' => 'Please provide at least one: wallet_id, private_key, or mnemonic',
			        'data' => $validator->errors()
			    ], 422);
			}

			if($request->has('wallet_id'))
			{
				$wallet = Wallet::findorfail($request->wallet_id);
				$value = Crypt::decryptString($wallet->private_key);
			}elseif($request->has('private_key')){
				$value = $request->private_key;
			}else{
				$value = $request->mnemonic;
			}

			//$walletAddress = $request->wallet_address;

            $scriptPath = public_path('web3/bnbRange.js');

			// Pass private key as argument
			$command = "node " . escapeshellarg($scriptPath) . " " . escapeshellarg($value);

			$output = trim(shell_exec($command));

			$data = json_decode($output,true);

			return response()->json($data);

		}catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function maxRangePOL(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
			    'wallet_id'   => 'nullable|integer|exists:wallets,id|required_without_all:private_key,mnemonic',
			    'private_key' => 'nullable|string|required_without_all:wallet_id,mnemonic',
			    'mnemonic'    => 'nullable|string|required_without_all:wallet_id,private_key',
			]);

			if ($validator->fails()) {
			    return response()->json([
			        'status' => false,
			        'message' => 'Please provide at least one: wallet_id, private_key, or mnemonic',
			        'data' => $validator->errors()
			    ], 422);
			}

			if($request->has('wallet_id'))
			{
				$wallet = Wallet::findorfail($request->wallet_id);
				$value = Crypt::decryptString($wallet->private_key);
			}elseif($request->has('private_key')){
				$value = $request->private_key;
			}else{
				$value = $request->mnemonic;
			}

			//$walletAddress = $request->wallet_address;

            $scriptPath = public_path('web3/polRange.js');

			// Pass private key as argument
			$command = "node " . escapeshellarg($scriptPath) . " " . escapeshellarg($value);

			$output = trim(shell_exec($command));

			$data = json_decode($output,true);

			return response()->json($data);

		}catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function maxRangeETH(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [
			    'wallet_id'   => 'nullable|integer|exists:wallets,id|required_without_all:private_key,mnemonic',
			    'private_key' => 'nullable|string|required_without_all:wallet_id,mnemonic',
			    'mnemonic'    => 'nullable|string|required_without_all:wallet_id,private_key',
			]);

			if ($validator->fails()) {
			    return response()->json([
			        'status' => false,
			        'message' => 'Please provide at least one: wallet_id, private_key, or mnemonic',
			        'data' => $validator->errors()
			    ], 422);
			}

			if($request->has('wallet_id'))
			{
				$wallet = Wallet::findorfail($request->wallet_id);
				$value = Crypt::decryptString($wallet->private_key);
			}elseif($request->has('private_key')){
				$value = $request->private_key;
			}else{
				$value = $request->mnemonic;
			}

			//$walletAddress = $request->wallet_address;

            $scriptPath = public_path('web3/ethRange.js');

			// Pass private key as argument
			$command = "node " . escapeshellarg($scriptPath) . " " . escapeshellarg($value);

			$output = trim(shell_exec($command));

			$data = json_decode($output,true);

			return response()->json($data);

		}catch (Exception $e) {
	        return response()->json([
	            'status'  => false,
	            'code'    => $e->getCode(),
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

}
