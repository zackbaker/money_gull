import {Component, Input, OnInit} from '@angular/core';
import { Router } from '@angular/router';

import {Account} from '../../models/account.model';
import {AccountService} from '../../services/account.service';
import {User} from '../../models/user.model';
import {UserService} from '../../services/user.service';
import {TransactionService} from '../../services/transaction.service';
import {ErrorComponent} from '../error/error.component';

@Component({
    selector: 'account',
    templateUrl: './account.component.html',
    styleUrls: ['./account.component.css']
})
export class AccountComponent implements OnInit {
    @Input() private account: Account;
    private user: User;
    private update_view: Boolean = false;
    private account_name: String;
    private account_amount: number;
    private errors: String[];

    constructor(
        private router: Router,
        private userService: UserService,
        private accountService: AccountService,
        private transactionService: TransactionService
    ) {}

    public ngOnInit(): void {
        this.user = this.userService.getUser();
        this.account_name = this.account.name;
        this.account_amount = this.account.amount;
    }

    protected goToAccount(): void {
        this.router.navigate(['/account', this.account.id]);
    }

    protected switchToUpdate(): void {
        this.update_view = true;
    }

    protected stopUpdate(): void {
        this.update_view = false;
    }

    protected updateAccount(): void {
        this.errors = this.checkForUpdateErrors();
        if (this.errors.length) {
            return;
        }

        if (
            this.account.name == this.account_name &&
            this.account.amount == this.account_amount
        ) {
            this.stopUpdate();
            return;
        }

        if (this.account.amount != this.account_amount) {
            this.addTransaction();
        }

        let account_update = {
            id: this.account.id,
            account_name: this.account_name,
            account_amount: this.account_amount,
        }

        this.accountService.updateAccount(this.user.id, account_update)
            .then(
                (updatedAccount) => {
                    this.account = updatedAccount.data;
                    this.stopUpdate();
                }
            );
    }

    private addTransaction(): void {
        let transaction_info = {};
        if (this.account.amount < this.account_amount) {
            transaction_info['type'] = 'expense';
            transaction_info['amount'] = this.account.amount - this.account_amount;
        } else {
            transaction_info['type'] = 'income';
            transaction_info['amount'] = this.account_amount - this.account.amount;
        }

        transaction_info['description'] = '';

        this.transactionService.createAccountTransaction(
            this.user.id,
            this.account.id,
            transaction_info
        );
    }

    private checkForUpdateErrors(): String[] {
        let errors = [];

        if (isNaN(this.account_amount)) {
            errors.push('Invalid Amount: Amount must be a number');
        }

        return errors;
    }
}