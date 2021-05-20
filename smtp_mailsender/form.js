function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function errorchecker(id,value,returnvalue){
    console.log("it works");
    if(returnvalue==0){
        $$('form').markInvalid(id,value);
        $$('submit').disable();
    }
    else{
        $$('form').markInvalid(id,false);
    }
}


function processSubmitWindowData() {
    var from = $$('from').getValue();
    var subject = $$('subject').getValue();
    var content = $$('content').getValue();
    var smtpport = $$('smtpport').getValue();
    var smtppass = $$('smtppass').getValue();
    var smtphost = $$('smtphost').getValue();
    var smtpuser = $$('smtpuser').getValue();
    console.log("Checking if submit has to be enabled or not");

    if(from==""||subject==""||content==""||smtphost==""||smtppass==""||smtpuser==""||smtpport==""){
      $$('submit').disable();
      console.log("from:");
      console.log("subject:"+subject);
      console.log("content:"+content);
      console.log("smtphost:"+smtphost);
      console.log("smtppass:"+smtppass);
      console.log("smtpuser:"+smtpuser);
      console.log("smtpport:"+smtpport);
      console.log("empty fields"); 
    }
    
    else if($$('form').validate()){
        console.log('the form has given a true validation');
        $$('submit').enable();
    }

        
    else{
        console.log('the form has given a false validation');
        $$('submit').disable();
    }
    
}

