# outputs.tf

output "alb_hostname" {
  value = aws_alb.main.dns_name
}


output "db_instance_address" {
  value = aws_db_instance.db_instance.address
}

