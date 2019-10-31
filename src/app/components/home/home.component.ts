import {Component, OnInit} from '@angular/core';

import {GoalComponent} from '../../shared/components/goal/goal.component';
import {TransactionsComponent} from '../../shared/components/transactions/transactions.component';
import {GoalService} from '../../shared/services/goal.service';
import {UserService} from '../../shared/services/user.service';
import {Goal} from '../../shared/models/goal.model';
import {User} from '../../shared/models/user.model';

@Component({
    selector: 'home',
    templateUrl: './home.component.html',
    styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
    private user: User;
    private goals: Goal[] = [];
    // Maybe let this be set by user
    // eventually?
    private goalCount = 2;

    constructor(
        private goalService: GoalService,
        private userService: UserService
    ) {}

    ngOnInit(): void {
        this.user = this.userService.getUser();
        this.goalService.getGoals(this.user.id)
            .then(
                (goal_list) => {
                    // This should be in the api
                    // i.e. pass in count argument
                    for (let i = 0; i < this.goalCount; i++) {
                        if (goal_list.data[i] instanceof Object) {
                            this.goals.push(goal_list.data[i]);
                        }
                    }
                }
            );
    }
}