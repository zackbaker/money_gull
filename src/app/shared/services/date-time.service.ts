import {Injectable} from '@angular/core';

@Injectable()
export class DateTimeService {
    constructor() {}

    getDateTime() {
        let date = new Date();

        return this.addZero(date.getFullYear()) + '-' +
                this.addZero(date.getMonth() + 1) + '-' +
                this.addZero(date.getDate()) + ' ' +
                this.addZero(date.getHours()) + ':' +
                this.addZero(date.getMinutes()) + ':' +
                this.addZero(date.getSeconds());
    }

    private addZero(num) {
        if (num < 10) {
            num = '0' + num
        }

        return num;
    }
}