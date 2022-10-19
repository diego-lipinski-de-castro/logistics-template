class Transaction {
  Transaction({
    required this.value,
    required this.balance,
    required this.valueFormatted,
    required this.balanceFormatted,
    required this.date,
    required this.description,
  });

  final double value;
  final double balance;
  final String valueFormatted;
  final String balanceFormatted;
  final String date;
  final String description;

  factory Transaction.fromMap(Map<String, dynamic> json) => Transaction(
        value: json["value"].toDouble(),
        balance: json["balance"].toDouble(),
        valueFormatted: json["value_formatted"],
        balanceFormatted: json["balance_formatted"],
        date: json["date"],
        description: json["description"],
      );

  Map<String, dynamic> toMap() => {
        "value": value,
        "balance": balance,
        "value_formatted": valueFormatted,
        "balance_formatted": balanceFormatted,
        "date": date,
        "description": description,
      };
}
