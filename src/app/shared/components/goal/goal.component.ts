import {Component, Input, OnInit} from '@angular/core';
import { Router } from '@angular/router';

import {ErrorComponent} from '../error/error.component';
import {GoalService} from '../../services/goal.service';
import {UserService} from '../../services/user.service';
import {TransactionService} from '../../services/transaction.service';
import {Goal} from '../../models/goal.model';
import {User} from '../../models/user.model';

@Component({
    selector: 'goal',
    templateUrl: './goal.component.html',
    styleUrls: ['./goal.component.css']
})
export class GoalComponent implements OnInit {
    @Input() private goal: Goal;
    @Input() private editable: Boolean;
    private update_goal: Goal;
    private user: User;
    private show_edit: Boolean = false;
    public errors: String[];

    public constructor(
        private goalService: GoalService,
        private userService: UserService,
        private transactionService: TransactionService,
        private router: Router
    ){}

    public ngOnInit(): void {
        this.user = this.userService.getUser();
        this.update_goal = {
            'id': this.goal.id,
            'name': this.goal.name,
            'needed': this.goal.needed,
            'saved': this.goal.saved,
        };
    }

    public goToGoal(): void {
        this.router.navigate(['/goal', this.goal.id]);
    }

    public showEdit(): void {
        this.show_edit = true;
    }

    public stopEdit(): void {
        this.show_edit = false;
    }

    public updateGoal(): void {
        if (
            this.goal.name == this.update_goal.name &&
            this.goal.needed == this.update_goal.needed &&
            this.goal.saved == this.update_goal.saved
        ) {
            this.stopEdit();
            return;
        }

        this.errors = this.checkForErrors();
        if (this.errors.length > 0) {
            return;
        }

        this.goalService.updateGoal(this.user.id, this.update_goal).then(
            (response) => {
                if (this.goal.saved != this.update_goal.saved) {
                    this.addTransaction();
                }

                this.goal = response.data;
                this.stopEdit();
            }
        );
    }

    private checkForErrors(): String[] {
        let errors = [];

        if (isNaN(this.update_goal.needed)) {
            errors.push('Invalid Amount Needed: Amount needed must be a number');
        }

        if (isNaN(this.update_goal.saved)) {
            errors.push('Invalid Amount Saved: Amount saved must be a number');
        }

        return errors;
    }

    private addTransaction(): void {
        let transaction_info = {};

        if (this.goal.saved > this.update_goal.saved) {
            transaction_info['type'] = 'expense';
            transaction_info['amount'] = this.goal.saved - this.update_goal.saved;
        } else {
            transaction_info['type'] = 'income';
            transaction_info['amount'] = this.update_goal.saved - this.goal.saved;
        }

        transaction_info['description'] = '';

        this.transactionService.createGoalTransaction(
            this.user.id,
            this.goal.id,
            transaction_info
        );
    }
}