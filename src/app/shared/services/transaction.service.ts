import {Injectable} from '@angular/core';
import {Http} from '@angular/http';
import 'rxjs/add/operator/toPromise';

import {environment} from '../../../environments/environment';

import {DateTimeService} from './date-time.service';

@Injectable()
export class TransactionService {
    constructor(
        private http: Http,
        private dateTimeService: DateTimeService
    ) {}

    public getTransactions(userId): Promise<any> {
        return this.http.get(
            environment.apiUrl + '/users/' + userId + '/transactions',
        )
        .toPromise()
        .then(
            (response) => {
                return response.json();
            }
        )
        .catch(
            (error) => {
                return error.json()
            }
        );
    }

    public createAccountTransaction(user_id, account_id, transactionInfo): Promise<any> {
        return this.http.post(
            environment.apiUrl + '/users/' + user_id + '/accounts/' + account_id + '/transactions',
            {
                'date': this.dateTimeService.getDateTime(),
                'type': transactionInfo.type,
                'amount': transactionInfo.amount,
                'description': transactionInfo.description,
            }
        )
        .toPromise()
        .then(
            (response) => {
                return response.json();
            }
        )
        .catch(
            (error) => {
                return error.json()
            }
        );
    }

    public createGoalTransaction(user_id, goal_id, transactionInfo): Promise<any> {
        return this.http.post(
            environment.apiUrl + '/users/' + user_id + '/goals/' + goal_id + '/transactions',
            {
                'date': this.dateTimeService.getDateTime(),
                'type': transactionInfo.type,
                'amount': transactionInfo.amount,
                'description': transactionInfo.description,
            }
        )
        .toPromise()
        .then(
            (response) => {
                return response.json();
            }
        )
        .catch(
            (error) => {
                return error.json()
            }
        );
    }
}