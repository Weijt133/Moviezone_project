# Moviezone_project

This MovieZone System is divided into two part: General System and Admin System. Each system includes three components: controller, view and model. The view component display all of data in need to the interface. JavaScript will get the request and parameters from interface then post to controller component. The controller based on the user request invokes the appropriate functionality from the model class, which consist of a variety of method to provide different functionality. And model handles adapter class which is allowed to operate the database system. Ultimately, Ajax get request data from controller, and then update content in specified div. Some good working in this assignment are as follows.
- User’s request is caught by JavaScript, and then posted to server after data processing, which can reduce the burden on the server.
- AJax post the instructions and parameters to server, which is faster than submitting a whole form data.
- The controller uses switch to read the instructions and get parameters.
- The controller calls modules and view components to complete operation in need.
- The controller request services of the model class function to services the client request by invoking the function with appropriate parameters.
- The controller returns requested data to Ajax, which updates specifies container on page.
- The model class deal with the database adapter classes to retrieve the needed data and sends it back to the controller
- The controller class passed the retrieve data or error if no data available to the view class function
- The view class formats the data and display the results to the end user.

The link of my assignment is: http://infotech.scu.edu.au/~jtang13/assignment2

