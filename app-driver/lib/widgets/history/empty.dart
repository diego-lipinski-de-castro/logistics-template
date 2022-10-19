import 'package:delivery/utils/colors.dart';
import 'package:flutter/material.dart';

class HistoryEmpty extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: EdgeInsets.all(20),
      child: Column(
        children: [
          SizedBox(
            height: MediaQuery.of(context).size.height / 8,
          ),
          Container(
            padding: EdgeInsets.all(25),
            decoration: BoxDecoration(
              color: Colors.grey,
              borderRadius: BorderRadius.circular(100),
            ),
            child: Icon(
              Icons.delivery_dining,
              color: CustomColors.white,
              size: 96,
            ),
          ),
          SizedBox(height: 20),
          Text(
            'Você ainda não realizou nenhuma entrega',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 18,
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}
