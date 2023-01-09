# tasks

<b>How To Run??<b>
After Starting Apache and MySQL in XAMPP, follow the following steps

1st Step: Extract file
  
2nd Step: Copy main project folder
  
3rd Step: Paste in xampp/htdocs/

  <b>Now Connecting Database</b>

4th Step: Open browser and go to URL GET - “http://localhost/tasks/index.php”

<b>Register Users</b>

5th Step: Open a browser and go to URL POST -“http://localhost/task/index.php/user/postRegisterUser”

<b>Login</b>

6th Step: Open a browser and go to URL POST - “http://localhost/task/index.php/user/login”
the response should be like this;
{
    "error": {
        "success": 1,
        "message": "You have successfully logged in.",
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L3Rhc2svIiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdC90YXNrLyIsImlhdCI6MTY3MzI5NzIyNCwiZXhwIjoxNjczMzAwODI0LCJkYXRhIjp7InVzZXJfaWQiOiIxMiJ9fQ.dDdWayhDeRmwblPL4YIWuQnWRFuMDKqqDknwyXoiFuk"
    }
}
<b>Get Current User Details</b>

7th Step: Open a browser and go to URL “http://localhost/task/index.php/user/getUser"
Please use token as bearer in headers
GET - http://localhost/task/index.php/user/getUser
Payload (Header)
Authorization - Bearer Token

After successfull token match;the reponse should be like this
{"success":1,"user":{"id":"12","0":"12","name":"username","1":"username","email":"useremail@mail.com","2":"useremail@mail.com","password":"$2y$10$GwIi0ExxO3MsNaXsNvDeSOqFLewf2qzF3Ncjw48x7.n\/rCEcrBPs2","3":"$2y$10$GwIi0ExxO3MsNaXsNvDeSOqFLewf2qzF3Ncjw48x7.n\/rCEcrBPs2"}}

<b>Create Task</b>

8th Step: Open a browser and go to URL "http://localhost/task/index.php/user/createTask"

sample json data for task creation;
{
    "subject":"task1",
    "description":"test data for task1",
    "status":"New",
    "priority" : "High",
    "start_date" : "2023-01-09 16:46:05",
    "due_date" : "2023-01-27 16:46:05",
     "notes": [{
	     	"subject": "note subject 1",
	     	"attachment": "test.docx",
	     	"note" : "test data for note "
	     },
	     {
	     	"subject": "note subject 2",
	     	"attachment": "test2.jpeg",
	     	"note" : "test data for note "
	     },
	     {
	     	"subject": "note subject 4",
	     	"attachment": "test3.pdf",
	     	"note" : "test data for note "
	     },
	     {
	     	"subject": "note subject 5",
	     	"attachment": "test3.png",
	     	"note" : "test data for note "
	     }
     ]
}
After successfull request the response should be like this;
{"error":{"success":1,"status":201,"message":"Task created successfully."}}

<b>For all tasks along with notes</b>

9th Step: Open a browser and go to URL "http://localhost/task/index.php/user/getAllNotes"
