const { ethers } = require("ethers");

// Read private key from command line argument
const privateKey = process.argv[2];

if (!privateKey) {
  console.log(JSON.stringify({
    status: false,
    message: "Please provide a private key"
  }));
  process.exit(1);
}

try {
  const wallet = new ethers.Wallet(privateKey);

  console.log(JSON.stringify({
    status: true,
    address: wallet.address
  }));
} catch (err) {
  console.log(JSON.stringify({
    status: false,
    message: "Invalid private key",
    error: err.message
  }));
}
