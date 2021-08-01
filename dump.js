var admin = require("firebase-admin");

var serviceAccount = require("./stupid-reference-code-firebase-adminsdk-lad17-e5377b1ec1.json");

admin.initializeApp({
  credential: admin.credential.cert(serviceAccount),
  databaseURL: "https://stupid-reference-code-default-rtdb.asia-southeast1.firebasedatabase.app"
});

async function main () {
  const o = []
  const pres = await admin.firestore().collection('Teams').get()
  for (const p of pres.docs) {
    o.push({...p.data(),key: p.id})
  }
  const fs =require('fs')
  fs.writeFileSync('data/presentations.json', JSON.stringify(o,null,2))
  const users = await admin.firestore().collection('Users').get()
  const uz = {}
  for (const u of users.docs) {
    uz[u.id] = {...u.data()}
  }
  fs.writeFileSync('data/users.json', JSON.stringify(uz,null,2))
}
main()