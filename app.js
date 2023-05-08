const cron = require('node-cron')
const { exec } = require('child_process');
const shell = require('shelljs')

cron.schedule("1 * * * * *", function(){
    exec('php artisan schedule:run', (err, stdout, stderr) => {
        if (err) {
            console.error(`exec error: ${err}`);
            return;
        }
        console.log(`stdout: ${stdout}`);
        console.error(`stderr: ${stderr}`);
    });
})
