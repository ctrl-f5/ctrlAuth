SET foreign_key_checks = 0;

INSERT INTO `ctrl_auth_user` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', SHA1('admin'), 'example@example.com');

INSERT INTO `ctrl_auth_role` (`id`, `name`, `system`) VALUES
(1, 'guest', 1),
(2, 'superuser', 1);

INSERT INTO `ctrl_auth_user_role` (`role_id`, `user_id`) VALUES
('2', '1');

INSERT INTO  `ctrl_auth_resource` (`id`, `resource`)VALUES
(1 , 'global'),
(2 , 'routes.CtrlAuth\\Controller'),
(3 , 'routes.CtrlAuth\\Controller\\Login'),
(4 , 'routes.CtrlAuth\\Controller\\User'),
(5 , 'routes.CtrlAuth\\Controller\\Role'),
(6 , 'routes.CtrlAuth\\Controller\\Permission');

INSERT INTO  `ctrl_auth_permission` (`id` , `role_id` , `resource_id` , `isAllowed`) VALUES
(NULL, '2',  '1',  '1'),
(NULL, '2',  '2',  '1'),
(NULL, '2',  '3',  '1'),
(NULL, '2',  '4',  '1'),
(NULL, '2',  '5',  '1'),
(NULL, '2',  '6',  '1');

SET foreign_key_checks = 1;