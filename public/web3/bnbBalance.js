// checkBnbBalance.js
const { ethers } = require("ethers");

async function getBnbBalance(walletAddress) {
    // Connect to BSC mainnet
    const provider = new ethers.providers.JsonRpcProvider("https://bsc-dataseed.binance.org/");

    try {
        const balanceWei = await provider.getBalance(walletAddress);
        const balanceBnb = ethers.utils.formatEther(balanceWei);
        return balanceBnb;
    } catch (err) {
        console.error("Error fetching balance:", err);
        return null;
    }
}

// Accept wallet address from command line argument
const walletAddress = process.argv[2];
if (!walletAddress) {
    console.log("Usage: node checkBnbBalance.js <wallet_address>");
    process.exit(1);
}

getBnbBalance(walletAddress).then(balance => {
    console.log(balance);
});
