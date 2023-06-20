const CronJob = require('cron').CronJob;
const axios = require('axios');

const job = new CronJob('* * * * *', function () {
    // Logika cron job Anda di sini
    axios.get(`http://127.0.0.1:8000/api/auth/status/reminder`)
    .then(response => {
        if (response.data.active == 1) {
            axios.get(`http://127.0.0.1:8000/api/auth/artisan`)
                .then(response => {
                console.log(response.data)
            })
        } else {
            console.log("Reminder is nonactive\n")
        }
    })
    .catch(error => {
        console.log(error)
    });
});

// Memulai cron job
job.start();
