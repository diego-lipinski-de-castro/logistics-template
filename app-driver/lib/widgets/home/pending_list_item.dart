import 'package:delivery/models/delivery.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:dotted_decoration/dotted_decoration.dart';
import 'package:flutter/material.dart';

class PendingListItem extends StatelessWidget {
  final Delivery delivery;
  final Function(Delivery) onDecline;
  final Function(Delivery) onAccept;

  const PendingListItem({
    Key? key,
    required this.delivery,
    required this.onDecline,
    required this.onAccept,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
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
          Container(
            color: CustomColors.orange,
            padding: EdgeInsets.symmetric(
              vertical: 10,
              horizontal: 5,
            ),
            width: double.infinity,
            child: Stack(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      delivery.lastmile
                          ? 'ENTREGA LASTMILE'
                          : 'ENTREGA EXPRESSA',
                      style: TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.w900,
                        letterSpacing: 0.25,
                      ),
                    ),
                    SizedBox(width: 5),
                    delivery.lastmile
                        ? Icon(
                            Icons.route,
                            color: Colors.white,
                          )
                        : Icon(
                            Icons.flash_on,
                            color: Colors.white,
                          ),
                  ],
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: GestureDetector(
                    onTap: () {
                      showDialog(
                        context: context,
                        builder: (BuildContext context) {
                          return AlertDialog(
                            content: Text(
                              'Uma entrega lastmile é a última etapa de um processo de transporte e entrega de mercadorias, referente a entrega final do produto ao consumidor.\n\nNesse estilo de entrega você deverá ir até o local de coleta e seguir com o processo de entrega a partir do aplicativo específico da plataforma.',
                            ),
                            actions: [
                              new FlatButton(
                                child: new Text('Voltar'),
                                onPressed: () {
                                  Navigator.of(context).pop();
                                },
                              ),
                            ],
                          );
                        },
                      );
                    },
                    child: Padding(
                      padding: const EdgeInsets.only(right: 10.0),
                      child: Icon(
                        Icons.info_rounded,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
          SizedBox(height: 20),
          Container(
            padding: EdgeInsets.symmetric(horizontal: 20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  delivery.cid,
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                  ),
                ),
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
                if (!delivery.lastmile) ...[
                  Text(
                    delivery.steps[0].formattedAddress,
                    style: TextStyle(
                      color: CustomColors.darkGrey,
                    ),
                  ),
                ],
                SizedBox(height: 20),
                Text(
                  delivery.lastmile
                      ? 'Endereço de coleta'
                      : 'Endereço de entrega',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                Text(
                  delivery.steps[1].formattedAddress,
                  style: TextStyle(
                    color: CustomColors.darkGrey,
                  ),
                ),
                SizedBox(height: 20),
                Text(
                  'Observações',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                Text(
                  delivery.publicText ?? '-',
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
                if (delivery.returnFeePaid != null) ...[
                  SizedBox(height: 20),
                  Container(
                    width: double.infinity,
                    child: Column(
                      children: [
                        Text(
                          'RETORNO NECESSÁRIO',
                          style: TextStyle(
                            color: CustomColors.orange,
                            fontSize: 16,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        Text(
                          'Você está recebendo um bônus pelo retorno',
                          style: TextStyle(
                            color: CustomColors.darkGrey,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
                SizedBox(height: 20),
                Container(
                  decoration: DottedDecoration(
                    shape: Shape.box,
                    borderRadius: BorderRadius.circular(10),
                    strokeWidth: 2,
                    color: CustomColors.orange,
                  ),
                  padding: EdgeInsets.symmetric(
                    vertical: 10,
                    horizontal: 5,
                  ),
                  width: double.infinity,
                  child: Column(
                    children: [
                      Text(
                        delivery.scheduledAt != null
                            ? 'ENTREGA AGENDADA'
                            : 'ENTREGA IMEDIATA',
                        style: TextStyle(
                          color: CustomColors.orange,
                          fontSize: 20,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      if (delivery.scheduledAt != null) ...[
                        Text(
                          delivery.formattedScheduledAt ?? '-',
                          style: TextStyle(
                            color: CustomColors.darkGrey,
                            fontSize: 20,
                            fontWeight: FontWeight.w600,
                          ),
                        )
                      ],
                      if (!delivery.lastmile) ...[
                        Text(
                          'Você terá até 90 minutos para coletar e finalizar essa entrega',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            color: CustomColors.darkGrey,
                            fontSize: 18,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ],
                  ),
                ),
                SizedBox(height: 20),
                Column(
                  children: [
                    Container(
                      width: double.infinity,
                      child: FlatButton(
                        onPressed: () {
                          onDecline(delivery);
                        },
                        height: 50,
                        color: CustomColors.red,
                        shape: StadiumBorder(),
                        padding: EdgeInsets.symmetric(
                          vertical: 10,
                          horizontal: 20,
                        ),
                        child: Text(
                          Strings.refuse,
                          style: TextStyle(
                            color: CustomColors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                    SizedBox(height: 10),
                    Container(
                      width: double.infinity,
                      child: FlatButton(
                        onPressed: () {
                          onAccept(delivery);
                        },
                        height: 50,
                        color: CustomColors.green,
                        shape: StadiumBorder(),
                        padding: EdgeInsets.symmetric(
                          vertical: 10,
                          horizontal: 20,
                        ),
                        child: Text(
                          Strings.accept,
                          style: TextStyle(
                            color: CustomColors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          SizedBox(height: 20),
        ],
      ),
    );
  }
}
