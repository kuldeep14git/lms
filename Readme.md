Built on PHP 8.1 and Drupal 10.1

Installation Instruction

1) git clone https://github.com/kuldeep14git/lms.git

2) cd lms

3) composer install

4) Go to browser and install drupal as you normally do, Select English as a language and Standard profile. 

5) Once installed Go to sites/default/settings.php and give it write permission if required.

6) Change config synch directory to this (you will find it probably in the last line)

       $settings['config_sync_directory'] = '../config/sync';
	
7) Now user drush to update UUID :

       drush cset system.site uuid "e1d84ff6-fc9f-4d0f-8b29-6a3fb1d190d8"
	   
7) Now remove shortlinks with below command :

       drush ev '\Drupal::entityTypeManager()->getStorage("shortcut_set")->load("default")->delete();'

8) Now run drush cim command
     
	   drush cim
	   
9) Once configuration import completes go to admin panel.

10) Now we are good to create content
    - Go to /node/add/lessons
	- Add some content
	- Use "Add Course Content" button to add more content
	- Add atleast 2 
	- Once you save, remember the saved node ID 
	
11) Now Lets create courses
    - Go to /node/add/courses
	- Add some content
	- Put your remebered node id in the "lesson reference" field
	- repeat the step 10 and 11 to add more courses and lessons
	
12) Create some users of student and instructor from the backend and verify the functionality.

13 Go to /user/dashboard to verify below functionality
    - Certificate generation
	- Porgress tracking for students