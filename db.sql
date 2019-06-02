-- docker run --rm -e 'MYSQL_ROOT_HOST=%' -e MYSQL_ROOT_PASSWORD=123456 -e MYSQL_USER='gandalf' -e MYSQL_PASSWORD='gandalf' -e MYSQL_DATABASE=test -p 3306:3306 mysql:5.7 &
-- mysql -u gandalf -pgandalf -h127.0.0.1
-- use test
create table note_availability (
    note int,
    quantity int
);

insert into note_availability (note, quantity) values (5, 1), (10, 2);
