const { ethers } = require("ethers");

// Read mnemonic from command line argument
const mnemonic = process.argv[2];

if (!mnemonic) {
  console.log(JSON.stringify({
    status: false,
    message: "Please provide a mnemonic"
  }));
  process.exit(1);
}

try {
  // Create wallet from mnemonic using standard ETH path
  const wallet = ethers.Wallet.fromMnemonic(mnemonic);

  console.log(JSON.stringify({
    status: true,
    address: wallet.address,
    privateKey: wallet.privateKey
  }));
} catch (err) {
  console.log(JSON.stringify({
    status: false,
    message: "Invalid mnemonic",
    error: err.message
  }));
}
