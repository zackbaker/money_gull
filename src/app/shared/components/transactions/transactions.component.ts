import {Component, OnInit, Input} from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';

import {TransactionService} from '../../services/transaction.service';
import {UserService} from '../../services/user.service';
import {User} from '../../models/user.model';
import {Transaction} from '../../models/transaction.model';

@Component({
    selector: 'transactions',
    templateUrl: './transactions.component.html',
    styleUrls: ['./transactions.component.css']
})
export class TransactionsComponent implements OnInit {
    @Input() private transactions: Transaction[] = [];
    private user: User;

    constructor(
        private router: Router,
        private transactionService: TransactionService,
        private userService: UserService
    ) {}

    ngOnInit(): void {
        this.user = this.userService.getUser();

        if (!this.transactions.length) {
            this.setTransactions(this.user.id);
        }
    }

    setTransactions(userId) {
        this.transactionService.getTransactions(userId).then(
            (transactions) => {
                this.transactions = transactions.data;
            }
        );
    }

    goToTransaction(transactionId): void {
        this.router.navigate(['/transaction', transactionId]);
    }
}