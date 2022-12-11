

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

	Registration plugin

		Click OK
			Login to the master
			Master updates the email, ipAddress and availability
			
		Click Cancel
		    If rendering then disallow Cancel
			Else call /status
			    Notify user in the dialog that there are outstanding jobs
			Still Cancel?
			Logout of master
			
		Timer Running
			AVAILABLE
				Get back an ACTIONINSTRUCTION
				AI_DO_RENDER
				    unzip the project file
				    kick off the render job
				
			AWAKE
				Get back an ACTIONINSTRUCTION
				AI_GET_COMPLETED - kick off the download of frames job
				
			AS_RENDERING
				Notify master that we are rendering
				When completed is detected notify master
			
	Render Submission plugin
	    Either accept default render settings or define custom ranges
	    Click OK
	        Check with user that the export of the project with assets is up to date
	        With /projects
	            zip up the project file giving it a unique name
	            upload the project to master
	        With /render submit the render request

	    Click Cancel
	        Just exit

	Master processing
        Renders

            OPEN
                Set in /render when creating the render
            READY
                Set in /render when the first detail record has been created, we needed the render id
            RENDERING
                Set in /rendering when a detail record is set to rendering
            COMPLETE
                Set in /complete when the final detail has been set to DONE

        Render Details

            READY
                When AVAILABLE comes in we assign it to the user and set it to ALLOCATED
                    We set the Render to RENDERING
            ALLOCATED
                Set in /available when we have been able to allocate it to a slave
            DONE
                Set in /complete called by the slave it was allocated to
                In /awake from the submitting slave we notify this is available
            RETURNED
                Set in /awake when slave notifies that the frame has been downloaded successfully
		
		API

		    // Slave registering with the system
            Route::post('/api1/register', function (Request $request) {
                User details are sent to the master
                Timer poll starts notifying master of availability
            }

            // Slave user notifying master that they are awake
            Route::post('/api1/awake', function (Request $request) {
                Action instruction tells slave to download frames
            }

            // Slave user requesting their current status in the system
            Route::post('/api1/status', function (Request $request) {
                Called when they attempt to Cancel the registration dialog
                This gives them a chance to avoid cancelling and continue polling
                You have render(s) READY or RENDERING
                You have render detail(s) READY or ALLOCATED
            }

            // Slave user notifying master that they are available and not currently rendering
            Route::post('/api1/available', function (Request $request) {
                Action instruction tells slave to commence rendering
            }

            // Slave user notifying master that they are rendering
            Route::post('/api1/rendering', function (Request $request) {

            // Slave user notifying master that the render they were working on is now complete
            Route::post('/api1/complete', function (Request $request) {

            // Slave user submitting a render to be processed
            Route::post('/api1/render', function (Request $request) {

            // Uploading project with assets file
            Route::post('/projects', function (Request $request) {

            // Uploading the rendered image files
            Route::post('/results', function (Request $request) {
			