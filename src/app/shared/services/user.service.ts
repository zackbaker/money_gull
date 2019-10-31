import {Injectable} from '@angular/core';
import {Http} from '@angular/http';
import 'rxjs/add/operator/toPromise';

import {environment} from '../../../environments/environment';

import {User} from '../../shared/models/user.model';

@Injectable()
export class UserService {
    private user: User;

    constructor(private http: Http) {}

    public signInCall(email, password): Promise<any> {
        let errors = this.errorCheck(email, password);

        if (errors.length > 0) {
            return Promise.resolve({"error": {"message": errors}});
        }

        return this.http.get(
            environment.apiUrl + '/users',
            {
                params: {
                    'email': email,
                    'password': password,
                }
            }
        )
        .toPromise()
        .then(
            (response) => {
                this.user = response.json().data;
                return response.json();
            }
        )
        .catch(
            (error) => {
                return error.json()
            }
        );
    }

    public signUpCall(email, password, username): Promise<any> {
        // hack! might have to make a new function just for signUp error checking
        let errors = this.errorCheck(email, password, typeof username);

        if (errors.length > 0) {
            return Promise.resolve({"error": {"message": errors}});
        }

        return this.http.post(
            environment.apiUrl + '/users',
            {
                'email': email,
                'password': password,
                'name': username,
            }
        )
        .toPromise()
        .then(
            (response) => {
                this.user = response.json().data;
                return response.json();
            }
        )
        .catch(
            (error) => {
                return error.json()
            }
        );
    }

    public getUser(): User {
        // return this.user;
        return {
            id: 1,
            email: 'test@test.com',
            name: 'testing'
        };
    }

    private errorCheck(email, password, username = null) {
        let errors = []

        if (!this.validateEmail(email)) {
            errors.push('Invalid Email: Please enter a valid email address');
        }

        // Should write in a better password structure
        // i.e. require number capital and special character
        if (typeof password == 'undefined' || password.length <= 0) {
            errors.push('Invalid password: Please enter a valid password');
        }

        if (username == 'undefined') {
            errors.push('Invalid username: Please enter a valid username');
        }

        return errors;
    }

    private validateEmail(email): boolean {
        var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(email);
    }
}