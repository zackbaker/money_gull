import {Component, OnInit, Output, EventEmitter} from '@angular/core';

import {UserService} from '../../services/user.service';
import {GoalService} from '../../services/goal.service';
import {User} from '../../models/user.model';
import {Goal} from '../../models/Goal.model';
import {ErrorComponent} from '../error/error.component';

@Component({
    selector: 'add-goal',
    templateUrl: './add-goal.component.html',
    styleUrls: ['./add-goal.component.css']
})
export class AddGoalComponent implements OnInit {
    @Output() public hideAddGoal = new EventEmitter<void>();
    @Output() public returnGoal = new EventEmitter<Account>();
    protected errors: String[];
    private user: User;
    private goal_name: String;
    private amount_needed: number;
    private amount_saved: number;

    constructor(
        private userService: UserService,
        private goalService: GoalService
    ) {}

    public ngOnInit(): void {
        this.user = this.userService.getUser();
    }

    protected hide(): void {
        this.hideAddGoal.emit();
    }

    protected saveGoal(): void {
        this.errors = this.checkForErrors();

        if (this.errors.length) {
            return;
        }

        let goal_info = {
            'goal_name': this.goal_name,
            'amount_needed': this.amount_needed,
            'amount_saved': this.amount_saved,
        };

        this.goalService.saveGoal(
            this.user.id,
            goal_info
        ).then(
            (response) => {
                this.addGoal(response.data);
                this.hide();
            }
        );
    }

    private checkForErrors(): String[] {
        let errors = [];

        if (isNaN(this.amount_needed)) {
            errors.push('Invalid Amount Needed: Amount needed must be a number');
        }

        if (isNaN(this.amount_saved)) {
            errors.push('Invalid Amount Saved: Amount saved must be a number');
        }

        return errors;
    }

    private addGoal(newGoal): void {
        console.log(newGoal);
        this.returnGoal.emit(newGoal);
    }
}