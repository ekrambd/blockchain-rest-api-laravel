const { ethers } = require("ethers");

// ===== CONFIG =====
// Use a more reliable Ethereum RPC
const RPC_URLS = [
    "https://eth.llamarpc.com",
    "https://rpc.ankr.com/eth"  // <-- optional, needs API key if required
];
const GAS_LIMIT = ethers.BigNumber.from("21000");
// ===================

// Input
const INPUT = process.argv[2];

if (!INPUT) {
    console.log(JSON.stringify({ status: false, message: "Private key or mnemonic is required" }, null, 2));
    process.exit(1);
}

async function getMaxETH() {
    let lastErr = null;

    for (const rpc of RPC_URLS) {
        try {
            const provider = new ethers.providers.JsonRpcProvider(rpc);

            let wallet;
            if (INPUT.trim().split(" ").length >= 12) {
                wallet = ethers.Wallet.fromMnemonic(INPUT.trim()).connect(provider);
            } else {
                wallet = new ethers.Wallet(INPUT.trim(), provider);
            }

            const balance = await provider.getBalance(wallet.address);
            const gasPrice = await provider.getGasPrice();
            const gasFee = gasPrice.mul(GAS_LIMIT);
            const maxSend = balance.sub(gasFee);

            console.log(JSON.stringify({
                status: true,
                network: "Ethereum",
                wallet_address: wallet.address,
                balance: ethers.utils.formatEther(balance),
                gas_fee: ethers.utils.formatEther(gasFee),
                max_send: maxSend.gt(0) ? ethers.utils.formatEther(maxSend) : "0",
                symbol: "ETH"
            }, null, 2));
            return;

        } catch (err) {
            lastErr = err;
        }
    }

    console.log(JSON.stringify({
        status: false,
        error: "All Ethereum RPC failed: " + lastErr.message
    }, null, 2));
}

getMaxETH();
