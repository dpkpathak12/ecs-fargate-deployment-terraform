resource "aws_db_instance" "db_instance" {
  allocated_storage    = 10
  engine               = "mysql"
  engine_version       = "8.0"
  instance_class       = "db.t2.micro"
  identifier           = var.mysql_identifier
  username             = var.mysql_username
  password             = var.mysql_password
  parameter_group_name = "default.mysql8.0"

  db_subnet_group_name = aws_db_subnet_group.db_subnet_group.name
  vpc_security_group_ids = [aws_security_group.vpc_sg.id]

  publicly_accessible = true
  skip_final_snapshot = true
}

resource "aws_db_subnet_group" "db_subnet_group" {
  name       = "${var.project_name}-db-subnet-group"
  subnet_ids   = aws_subnet.public.*.id

 tags = {
    Name = var.project_name
  }

}