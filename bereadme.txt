

Goto:  /Users/brianetheridge/Homestead

Run: vagrant up

Run: vagrant ssh # to access the running environment and MySQL

cd Code/srsapi


# db
    create database srs;

    GRANT ALL ON srs.* TO 'brian'@'localhost' IDENTIFIED BY 'Canopy9098';
    GRANT ALL ON srs.* TO 'srs_admin'@'localhost' IDENTIFIED BY 'Candoobly9';

    php artisan migrate

# bash scripting
    ######## To invoke python !/usr/bin/env python3
    # Example
    # nohup python /path/to/test.py &

==================================================================

TODO LIST

	On render request show dialog:
		 do you want to save the current project to current location?
		 with or without assets?
		 	with: fresh save project with assets and send that off
			without: save just the project and send that off
			
		remove from and to range and just use it in the background
		
		output format
			remove that and pick it up from the settings
			
		remove the project name and output format from dialog
		
		download images/psds as and when they are done, not wait for the whole render to be done
		
		

	Registration plugin

		Click OK
			Login to the master
			Master updates the email and availability
			
			Generate unique names for any uploads
			
		Click Cancel
		    If rendering then disallow Cancel
			Else call /status
			    Notify user in the dialog that there are outstanding jobs
			Still Cancel?
			
			Reset the render that is half done and reallocate to someone else
			
			Logout of master - kill sessions and rescind token
			
	Render Submission plugin
	
		Support package project with assets and test different size projects

		Support render without multipass
		
		When rendering do not necessarily do multipass

	Master processing
		
		Detect when a render has been cancelled on a slave
		
		API
			We need to have security on the API
			
			Pass tokens back and forth

            // Slave user requesting their current status in the system
            Route::post('/api1/status', function (Request $request) {
                Called when they attempt to Cancel the registration dialog
                This gives them a chance to avoid cancelling and continue polling
                You have render(s) READY or RENDERING
                You have render detail(s) READY or ALLOCATED
            }
			
		Admin functions
		
			List users
			
				Add new user
				
			List Render Jobs
			
				Allow cancel
				Allow cancel and reallocate
				
	