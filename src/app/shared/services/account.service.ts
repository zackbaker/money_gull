import {Injectable} from '@angular/core';
import {Http} from '@angular/http';
import 'rxjs/add/operator/toPromise';

import {environment} from '../../../environments/environment';

@Injectable()
export class AccountService {
    constructor(private http: Http) {}

    getAllAccounts(user_id): Promise<any> {
        return this.http.get(
            environment.apiUrl + '/users/' + user_id + '/accounts'
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

    getAccount(user_id, account_id): Promise<any> {
        return this.http.get(
            environment.apiUrl + '/users/' + user_id + '/accounts/' + account_id
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

    saveAccount(user_id, account_name, account_amount): Promise<any> {
        return this.http.post(
            environment.apiUrl + '/users/' + user_id + '/accounts',
            {
                'account_name': account_name,
                'account_amount': account_amount,
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

    updateAccount(user_id, account): Promise<any> {
        return this.http.put(
            environment.apiUrl + '/users/' + user_id + '/accounts/' + account.id,
            {},
            {
                params: {
                    'account_name': account.account_name,
                    'account_amount': account.account_amount,
                }
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