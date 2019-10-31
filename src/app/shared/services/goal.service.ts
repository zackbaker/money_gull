import {Injectable} from '@angular/core';
import {Http} from '@angular/http';
import 'rxjs/add/operator/toPromise';

import {environment} from '../../../environments/environment';

import {Goal} from '../models/goal.model';

@Injectable()
export class GoalService {
    public constructor(private http: Http) {}

    public getGoals(user_id): Promise<any> {
        return this.http.get(
            environment.apiUrl + '/users/' + user_id + '/goals'
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

    public saveGoal(user_id, goal_info): Promise<any> {
        return this.http.post(
            environment.apiUrl + '/users/' + user_id + '/goals',
            goal_info
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

    public updateGoal(user_id, goal_info): Promise<any> {
        return this.http.put(
            environment.apiUrl + '/users/' + user_id + '/goals/' + goal_info.id,
            {},
            {
                params: {
                    'goal_name': goal_info.name,
                    'amount_needed': goal_info.needed,
                    'amount_saved': goal_info.saved,
                },
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