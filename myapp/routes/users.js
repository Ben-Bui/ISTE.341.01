var express = require('express');
var router = express.Router();

/* GET users listing. /user */
router.get('/', function(req, res, next) {
  res.send('respond with a resource');
});

// Get user and respond with JSON
router.get("/:id",(req,res,next)=>{
  console.log("The ID is:  "+ req.params.id);
  let fakeUser = {
    age: 31,
    name: "Arthur Morgan",
    twitter: "artm",
    username: "amorgan",
  };
  res.json(fakeUser);
});

module.exports = router;
