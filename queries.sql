
update renders set status='ready';
update render_details set allocated_to_user_id=0, status='ready';
	
select * from render_details where 1;
select * from renders where 1;

delete from render_details where 1;
delete from renders where 1;
	
INSERT INTO `renders` VALUES (9,1,'ready','2022-12-05 13:29:28','2022-12-05 13:26:44','2022-12-05 13:29:28'),(10,2,'ready','2022-12-05 13:29:38','2022-12-05 13:26:44','2022-12-05 13:29:38');

INSERT INTO `render_details` VALUES (3,9,0,'ready','2022-12-05 13:28:00','2022-12-05 13:29:28'),(4,10,0,'ready','2022-12-05 13:28:00','2022-12-05 13:29:38');