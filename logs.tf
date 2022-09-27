# logs.tf

# Set up CloudWatch group and log stream and retain logs for 30 days
resource "aws_cloudwatch_log_group" "snipe_log_group" {
  name              = "/ecs/cb-app"
  retention_in_days = 30

  tags = {
    Name = "snipe-log-group"
  }
}

resource "aws_cloudwatch_log_stream" "snipe_log_stream" {
  name           = "snipe-log-stream"
  log_group_name = aws_cloudwatch_log_group.snipe_log_group.name
}

