var express = require('express');//need this
var router = express.Router();//need this

//used by every contact route
router.use((req,res,next)=>{
  let method = req.method;
  if (method === 'POST') {
    console.log('Body:'+JSON.stringify(req.body));
  }else{
    console.log("Not a POST");
  }
  next();
});

//append the date to nay post to /contact
router.post('/',(req,res,next)=>{
  const {name,message} = req.body;//destructuring 
  const now = new Date();
  req.data = { 
  //add data property to request object
  date: now,
  message,
  name,  
  };
  next();
});

/* GET index page. */
router.get('/', function(req, res, next) {
  res.render('contact', { title: 'Contact' });
});

router.post("/",function(req,res,next){
  var queryString = 
  'date='+
  encodeURIComponent(req.data.date) +  
  '&name='+
  encodeURIComponent(req.body.name) +
  '&message=' + 
  encodeURIComponent(req.body.message);

  res.redirect('/contact/thanks?'+queryString);
});

router.get('/thanks', function(req, res, next) {
  res.render('thanks', { 
    title: 'Thanks',
    date: req.query.date,
    name:req.query.name,
  message: req.query.message, 
  });
});

module.exports = router;//need this
