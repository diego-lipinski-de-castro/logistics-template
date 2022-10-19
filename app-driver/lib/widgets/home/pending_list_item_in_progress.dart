import 'package:delivery/models/delivery.dart';
import 'package:delivery/utils/colors.dart';
import 'package:flutter/material.dart';

class PendingListItemInProgress extends StatelessWidget {
  final Delivery delivery;
  final void Function(Delivery) onTap;

  const PendingListItemInProgress({
    Key? key,
    required this.delivery,
    required this.onTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: CustomColors.white,
        boxShadow: [
          BoxShadow(
            color: CustomColors.black.withOpacity(0.2),
            offset: Offset(0, 5),
            blurRadius: 15,
            spreadRadius: -5,
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Solicitado às ${delivery.createdAt.hour}:${delivery.createdAt.minute} de ${delivery.createdAt.day}/${delivery.createdAt.month}',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 16,
            ),
          ),
          SizedBox(height: 20),
          Text(
            delivery.user.name,
            style: TextStyle(
              color: CustomColors.orange,
              fontSize: 18,
              fontWeight: FontWeight.w600,
            ),
          ),
          Text(
            delivery.steps[0].formattedAddress,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 20),
          Text(
            'Cliente:',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            (null == delivery.customerName) ? '-' : delivery.customerName!,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 20),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Taxa de entrega',
                style: TextStyle(
                  fontSize: 18,
                  color: CustomColors.darkGrey,
                  fontWeight: FontWeight.w600,
                ),
              ),
              Text(
                delivery.formattedTotalPaid,
                style: TextStyle(
                  fontSize: 18,
                  color: CustomColors.darkGrey,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
          SizedBox(height: 20),
          Container(
            child: FlatButton(
              onPressed: () {
                onTap(delivery);
              },
              minWidth: double.infinity,
              height: 50,
              color: Color(0xFF42AA52),
              shape: StadiumBorder(),
              padding: EdgeInsets.symmetric(
                vertical: 10,
                horizontal: 20,
              ),
              child: Text(
                'CONTINUAR ENTREGA',
                style: TextStyle(
                  color: CustomColors.white,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
