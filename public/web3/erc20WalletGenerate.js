const { ethers } = require("ethers");

const wallet = ethers.Wallet.createRandom();

const result = {
    address: wallet.address,
    privateKey: wallet.privateKey,
    mnemonic: wallet.mnemonic.phrase
};

console.log(JSON.stringify(result));
