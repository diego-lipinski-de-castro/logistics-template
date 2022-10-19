import 'package:delivery/utils/colors.dart';
import 'package:flutter/material.dart';

class PendingEmpty extends StatelessWidget {
  final bool online;

  const PendingEmpty({Key? key, required this.online}) : super(key: key);

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
              color: online ? Color(0xFF52B060) : Colors.grey,
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
            online ? 'Você está ONLINE' : 'Você está OFFLINE',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 18,
            ),
            textAlign: TextAlign.center,
          ),
          SizedBox(height: 5),
          Text(
            online
                ? 'Aguardando novas entregas'
                : 'Fique online no menu para aceitar novas entregas!',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}
