import Vue from "vue";
import moment from 'moment'

Vue.filter('formatDate' , function(value) {
    if (value) {
        let date_string = moment(String(value)).format("DD-M-YYYY");

        let split = date_string.split('-');

        let month = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        return split[0] + ' ' + month[split[1] - 1]  + ' ' + split[2];
    }
})