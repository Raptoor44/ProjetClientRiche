@if not exist ".vscode" mkdir ".vscode"
@echo { 												> .vscode\settings.json
@echo 	"intelephense.environment.includePaths": [		>> .vscode\settings.json
@echo 		"C:\\Users\\%USERNAME%\\symfony\\vendor"	>> .vscode\settings.json
@echo 	]												>> .vscode\settings.json
@echo }													>> .vscode\settings.json
php composer.phar config cache-dir --unset
php composer.phar config cache-dir "%USERPROFILE%\symfony\cache"
php composer.phar config vendor-dir --unset
php composer.phar config vendor-dir "%USERPROFILE%\symfony\vendor"
php composer.phar config bin-dir --unset
php composer.phar config bin-dir "%USERPROFILE%\symfony\bin"
php composer.phar install