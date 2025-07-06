

Set WshShell = CreateObject("WScript.Shell")
WshShell.CurrentDirectory = "E:\magang-batch5\dashboard\app"
WshShell.Run "E:\magang-batch5\dashboard\app\restart_server.bat", 0, False
