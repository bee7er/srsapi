drop table migrations;
drop table password_resets;
drop table render_details;
drop table renders;
drop table users;

update renders set status='ready', completed_at=null;
update render_details set allocated_to_user_id=0, status='ready';

update renders set status='complete', completed_at=null;
update render_details set allocated_to_user_id=0, status='done';

update renders set status='complete', completed_at=null where id=11;
update render_details set allocated_to_user_id=2, status='done' where render_id=11;

select * from render_details where 1;
select * from renders where 1;

delete from render_details where 1;
delete from renders where 1;
	
INSERT INTO `renders` VALUES (13,1,'ready','','','2022-12-05 13:29:28','2022-12-05 13:26:44','2022-12-05 13:29:28');

INSERT INTO `render_details` VALUES (2,1,0,18,23,'done','2022-12-05 13:28:00','2022-12-05 13:29:28');

