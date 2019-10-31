import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';

import {GoalService} from '../../shared/services/goal.service';
import {UserService} from '../../shared/services/user.service';
import {User} from '../../shared/models/user.model';
import {Goal} from '../../shared/models/goal.model';

@Component({
    selector: 'goals-list',
    templateUrl: './goals-list.component.html',
    styleUrls: ['./goals-list.component.css']
})
export class GoalsListComponent implements OnInit {
    private goal_visible: Boolean = true;
    private user: User;
    private message: String;
    private goals: Goal[];

    public constructor(
        private userService: UserService,
        private goalService: GoalService,
        private router: Router
    ) {}

    public ngOnInit(): void {
        this.user = this.userService.getUser();
        this.setGoals(this.user.id);
    }

    private setGoals(userId): void {
        this.goalService.getGoals(userId).then(
            (goals) => {
                if (goals.hasOwnProperty('error')) {
                    this.message = goals.error.message;
                } else {
                    this.goals = goals.data;
                }
            }
        );
    }

    public addGoal(goal: Goal) {
        this.goals.push(goal);
    }

    public showAddGoal(): void {
        this.goal_visible = false;
    }

    public hideAddGoal(): void {
        this.goal_visible = true;
    }
}