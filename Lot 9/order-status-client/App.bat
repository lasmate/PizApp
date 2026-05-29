@echo off
cd /d "e:\xampp\htdocs\projets\PizApp\Lot 9\order-status-client"
call mvn clean compile exec:java -Dexec.mainClass="com.pizapp.client.App"
pause