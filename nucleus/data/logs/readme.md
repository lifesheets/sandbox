## Logs

The **Logs** section of the Catalog Project is essential for tracking and debugging the application's behavior. Logs provide detailed information about system operations, user activities, errors, and performance metrics. This feature ensures that issues can be quickly identified and resolved, contributing to the project's stability and reliability.

### Log Features
- **Error Logging**: Captures and records application errors to help diagnose issues.
- **Access Logs**: Keeps track of user interactions and activity within the system for monitoring and auditing purposes.
- **Performance Metrics**: Logs data related to the performance of various processes, such as response times and server load.
- **Custom Log Levels**: Configurable logging levels (e.g., DEBUG, INFO, WARN, ERROR) for different types of information.

### Log File Structure
Logs are stored in the `logs` directory and are organized by date. Each log file is named in the following format:

log-YYYY-MM-DD.log

### Viewing Logs
To view the logs, navigate to the `logs` directory and open the respective log file. You can use a text editor or command-line tools such as `cat`, `less`, or `tail`:
```bash
tail -f logs/log-2024-12-09.log
```
Log Rotation
To prevent excessive growth of log files, the project includes automatic log rotation and archiving. Old logs are compressed and stored in an archive folder for long-term access and historical analysis.