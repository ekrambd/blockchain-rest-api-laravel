const { ethers } = require("ethers");

// ===== CONFIG =====
const RPC_URL = "https://polygon-rpc.com"; // Polygon mainnet RPC
const GAS_LIMIT = ethers.BigNumber.from("21000");
// ===================

// 🔐 Input from CLI
// node getMaxMATIC.js "<private_key_or_mnemonic>"
const INPUT = process.argv[2];

if (!INPUT) {
    console.log(JSON.stringify({
        status: false,
        message: "Private key or mnemonic is required"
    }, null, 2));
    process.exit(1);
}

async function getMaxMATIC() {
    try {
        const provider = new ethers.providers.JsonRpcProvider(RPC_URL);

        let wallet;

        // ✅ Detect mnemonic vs private key
        if (INPUT.trim().split(" ").length >= 12) {
            // Mnemonic
            wallet = ethers.Wallet.fromMnemonic(INPUT.trim()).connect(provider);
        } else {
            // Private Key
            wallet = new ethers.Wallet(INPUT.trim(), provider);
        }

        const balance = await provider.getBalance(wallet.address);
        const gasPrice = await provider.getGasPrice();
        const gasFee = gasPrice.mul(GAS_LIMIT);

        const maxSend = balance.sub(gasFee);

        const response = {
            status: true,
            network: "Polygon",
            wallet_address: wallet.address,
            balance: ethers.utils.formatEther(balance),
            gas_fee: ethers.utils.formatEther(gasFee),
            max_send: maxSend.gt(0)
                ? ethers.utils.formatEther(maxSend)
                : "0",
            symbol: "POL"
        };

        console.log(JSON.stringify(response, null, 2));

    } catch (err) {
        console.log(JSON.stringify({
            status: false,
            error: err.message
        }, null, 2));
    }
}

getMaxMATIC();
