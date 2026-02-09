const { ethers } = require("ethers");

async function getMaticBalance(walletAddress) {
    // Connect to Polygon Mainnet
    const provider = new ethers.providers.JsonRpcProvider(
        "https://polygon-rpc.com"
    );

    try {
        const balanceWei = await provider.getBalance(walletAddress);
        const balanceMatic = ethers.utils.formatEther(balanceWei);
        return balanceMatic;
    } catch (err) {
        console.error("Error fetching balance:", err.message);
        return null;
    }
}

// CLI arg
const walletAddress = process.argv[2];
if (!walletAddress) {
    console.log("Usage: node checkMaticBalance.js <wallet_address>");
    process.exit(1);
}

getMaticBalance(walletAddress).then(balance => {
    console.log(balance !== null ? balance : "0");
});
