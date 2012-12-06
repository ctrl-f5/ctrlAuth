SET foreign_key_checks = 0;

INSERT INTO `ctrl_auth_user` (`id`, `system`, `username`, `password`, `email`) VALUES
(1, 1, 'admin', SHA1('admin'), 'example@example.com');

INSERT INTO `ctrl_auth_role` (`id`, `name`, `system`) VALUES
(1, 'guest', 1),
(2, 'superuser', 1),
(3, 'auth-guest', 0),
(4, 'auth-user', 0),
(5, 'auth-admin', 0);

INSERT INTO `ctrl_auth_role_map` (`parent_id`, `role_id`, `ordering`) VALUES
(3, 1, 1),
(5, 2, 1),
(1, 2, 2);

INSERT INTO `ctrl_auth_user_role` (`role_id`, `user_id`) VALUES
('2', '1');

INSERT INTO  `ctrl_auth_resource` (`id`, `resource`)VALUES
(1 , 'global'),
(2 , 'routes'),
(3 , 'routes.CtrlAuth\\Controller.Login'),
(4 , 'CtrlAuth.actions.User.remove');

INSERT INTO  `ctrl_auth_permission` (`id` , `role_id` , `resource_id` , `isAllowed`) VALUES
(NULL, '3',  '3',  '1'),
(NULL, '2',  '1',  '1'),
(NULL, '2',  '2',  '1'),
(NULL, '5',  '4',  '1');

SET foreign_key_checks = 1;