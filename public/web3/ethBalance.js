const { ethers } = require("ethers");

async function getEthBalance(walletAddress) {

    const provider = new ethers.providers.StaticJsonRpcProvider(
        "https://eth.llamarpc.com",
        {
            name: "homestead",
            chainId: 1
        }
    );

    try {
        const balanceWei = await provider.getBalance(walletAddress);
        const balanceEth = ethers.utils.formatEther(balanceWei);
        return balanceEth;
    } catch (err) {
        console.error("Error fetching balance:", err.message);
        return null;
    }
}

const walletAddress = process.argv[2];
if (!walletAddress) {
    console.log("Usage: node ethBalance.js <wallet_address>");
    process.exit(1);
}

getEthBalance(walletAddress).then(balance => {
    console.log(balance !== null ? balance : "0");
});
