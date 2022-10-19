import 'dart:typed_data';

import 'package:delivery/controllers/tracking.dart';
import 'package:delivery/models/delivery.dart';
import 'package:delivery/utils/colors.dart';
import 'package:delivery/utils/strings.dart';
import 'package:flutter/material.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';
import 'package:get/get_state_manager/get_state_manager.dart';
import 'package:delivery/models/delivery.dart' as Delivery;
import 'package:modal_bottom_sheet/modal_bottom_sheet.dart';

class TrackingPage extends GetView<TrackingController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color(0xFFF7F7F7),
      appBar: AppBar(
        backgroundColor: CustomColors.white,
        leading: BackButton(
          color: CustomColors.orange,
        ),
        title: Text(
          'Entrega em andamento',
          style: TextStyle(color: CustomColors.orange),
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        child: Container(
          width: double.infinity,
          child: Column(
            children: [
              Obx(() {
                return Column(
                  children: [
                    Padding(
                      padding: EdgeInsets.only(
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 5,
                      ),
                      child: Text(
                        'Entrega #${controller.delivery.cid}',
                        style: TextStyle(
                          color: CustomColors.darkGrey,
                          fontSize: 16,
                        ),
                      ),
                    ),
                    Padding(
                      padding: EdgeInsets.only(
                        left: 20,
                        right: 20,
                        top: 5,
                        bottom: 20,
                      ),
                      child: Text(
                        controller.delivery.formattedStatus,
                        style: TextStyle(
                          color: CustomColors.darkGrey,
                          fontSize: 16,
                        ),
                      ),
                    )
                  ],
                );
              }),
              Obx(() {
                switch (controller.delivery.status) {
                  case DeliveryStatuses.ACCEPTED:
                    return _stepCollect(context);
                  case DeliveryStatuses.COLLECTING:
                    return _stepDeliver(context);
                  case DeliveryStatuses.DELIVERING:
                    return _stepConfirm(context);
                  case DeliveryStatuses.RETURNING:
                    return _stepReturn(context);
                  default:
                    return SizedBox();
                }
              }),
              if (controller.delivery.status != DeliveryStatuses.COMPLETED) ...[
                SizedBox(height: 40),
                Container(
                  width: double.infinity,
                  margin: EdgeInsets.symmetric(horizontal: 20),
                  child: FlatButton(
                    onPressed: () {
                      controller.openWhatsapp(
                        'Quero cancelar a entrega #${controller.delivery.cid}',
                      );
                    },
                    height: 50,
                    color: CustomColors.red,
                    shape: StadiumBorder(),
                    padding: EdgeInsets.symmetric(
                      vertical: 10,
                      horizontal: 20,
                    ),
                    child: Text(
                      Strings.trackingRequestCancel,
                      style: TextStyle(
                        color: CustomColors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
                SizedBox(
                  height: MediaQuery.of(context).viewPadding.bottom + 20,
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _stepCollect(BuildContext context) {
    Delivery.Step step = controller.delivery.steps[0];

    return Container(
      width: double.infinity,
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
            'Solicitado às ${controller.delivery.createdAt.hour}:${controller.delivery.createdAt.minute} de ${controller.delivery.createdAt.day}/${controller.delivery.createdAt.month}',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 16,
            ),
          ),
          SizedBox(height: 20),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Flexible(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      controller.delivery.user.name,
                      style: TextStyle(
                        color: CustomColors.orange,
                        fontSize: 18,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    SizedBox(height: 10),
                    Text(
                      step.formattedAddress,
                      style: TextStyle(
                        color: CustomColors.darkGrey,
                      ),
                    ),
                  ],
                ),
              ),
              Row(
                children: [
                  Container(
                    margin: EdgeInsets.only(left: 20),
                    decoration: BoxDecoration(
                      color: Colors.grey,
                      borderRadius: BorderRadius.circular(100),
                    ),
                    child: IconButton(
                      icon: Icon(
                        Icons.copy,
                        color: CustomColors.white,
                      ),
                      onPressed: () {
                        controller.copyStepAddress(step);
                      },
                    ),
                  ),
                  Container(
                    margin: EdgeInsets.only(left: 10),
                    decoration: BoxDecoration(
                      color: CustomColors.orange,
                      borderRadius: BorderRadius.circular(100),
                    ),
                    child: IconButton(
                      icon: Icon(
                        Icons.directions,
                        color: CustomColors.white,
                      ),
                      onPressed: () {
                        controller.openMapsToStep(step);
                      },
                    ),
                  ),
                ],
              ),
            ],
          ),
          SizedBox(height: 20),
          Container(
            width: double.infinity,
            child: FlatButton(
              onPressed: controller.loading ? null : controller.collect,
              height: 50,
              color: Color(0xFF42AA52),
              disabledColor: Color(0xFF42AA52).withOpacity(0.5),
              shape: StadiumBorder(),
              padding: EdgeInsets.symmetric(
                vertical: 10,
                horizontal: 20,
              ),
              child: controller.loading
                  ? SpinKitThreeBounce(
                      color: Colors.white,
                      size: 15,
                    )
                  : Text(
                      'Cheguei no estabelecimento',
                      style: TextStyle(
                        color: CustomColors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _stepDeliver(BuildContext context) {
    Delivery.Step step = controller.delivery.steps[1];

    return Container(
      width: double.infinity,
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
            'Solicitado às ${controller.delivery.createdAt.hour}:${controller.delivery.createdAt.minute} de ${controller.delivery.createdAt.day}/${controller.delivery.createdAt.month}',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 16,
            ),
          ),
          SizedBox(height: 10),
          Text(
            'Cliente:',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            (null == controller.delivery.customerName)
                ? '-'
                : controller.delivery.customerName!,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 10),
          Text(
            'Endereço de entrega:',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            step.formattedAddress,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 10),
          Text(
            'Observações:',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            null == controller.delivery.publicText
                ? '-'
                : controller.delivery.publicText!,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 10),
          Text(
            'Outras informações',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            null == controller.delivery.additionalInfo
                ? '-'
                : controller.delivery.additionalInfo!,
            softWrap: true,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 10),
          Text(
            'Taxa de entrega:',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            controller.delivery.formattedTotalPaid,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 20),
          Container(
            width: double.infinity,
            child: FlatButton(
              onPressed: controller.loading ? null : controller.deliver,
              height: 50,
              color: Color(0xFF42AA52),
              disabledColor: Color(0xFF42AA52).withOpacity(0.5),
              shape: StadiumBorder(),
              padding: EdgeInsets.symmetric(
                vertical: 10,
                horizontal: 20,
              ),
              child: controller.loading
                  ? SpinKitThreeBounce(
                      color: Colors.white,
                      size: 15,
                    )
                  : Text(
                      'Realizar entrega',
                      style: TextStyle(
                        color: CustomColors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _stepConfirm(BuildContext context) {
    Delivery.Step step = controller.delivery.steps[1];

    return Container(
      width: double.infinity,
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
            'Solicitado às ${controller.delivery.createdAt.hour}:${controller.delivery.createdAt.minute} de ${controller.delivery.createdAt.day}/${controller.delivery.createdAt.month}',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 16,
            ),
          ),
          SizedBox(height: 10),
          Text(
            'Cliente:',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            (null == controller.delivery.customerName)
                ? '-'
                : controller.delivery.customerName!,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 10),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Flexible(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Telefone:',
                      style: TextStyle(
                        color: CustomColors.darkGrey,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    Text(
                      (null == controller.delivery.customerPhone)
                          ? '-'
                          : controller.delivery.customerPhone!,
                      style: TextStyle(
                        color: CustomColors.darkGrey,
                      ),
                    ),
                  ],
                ),
              ),
              if (null != controller.delivery.customerPhone) ...[
                Row(
                  children: [
                    Container(
                      margin: EdgeInsets.only(left: 20),
                      decoration: BoxDecoration(
                        color: Colors.grey,
                        borderRadius: BorderRadius.circular(100),
                      ),
                      child: IconButton(
                        icon: Icon(
                          Icons.copy,
                          color: CustomColors.white,
                        ),
                        onPressed: controller.copyCustomerPhone,
                      ),
                    ),
                    Container(
                      margin: EdgeInsets.only(left: 10),
                      decoration: BoxDecoration(
                        color: CustomColors.orange,
                        borderRadius: BorderRadius.circular(100),
                      ),
                      child: IconButton(
                        icon: Icon(
                          Icons.phone,
                          color: CustomColors.white,
                        ),
                        onPressed: controller.openPhoneDialogToCustomer,
                      ),
                    ),
                  ],
                ),
              ],
            ],
          ),
          SizedBox(height: 10),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Flexible(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Endereço de entrega:',
                      style: TextStyle(
                        color: CustomColors.darkGrey,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    Text(
                      step.formattedAddress,
                      style: TextStyle(
                        color: CustomColors.darkGrey,
                      ),
                    ),
                  ],
                ),
              ),
              Row(
                children: [
                  Container(
                    margin: EdgeInsets.only(left: 20),
                    decoration: BoxDecoration(
                      color: Colors.grey,
                      borderRadius: BorderRadius.circular(100),
                    ),
                    child: IconButton(
                      icon: Icon(
                        Icons.copy,
                        color: CustomColors.white,
                      ),
                      onPressed: () {
                        controller.copyStepAddress(step);
                      },
                    ),
                  ),
                  Container(
                    margin: EdgeInsets.only(left: 10),
                    decoration: BoxDecoration(
                      color: CustomColors.orange,
                      borderRadius: BorderRadius.circular(100),
                    ),
                    child: IconButton(
                      icon: Icon(
                        Icons.directions,
                        color: CustomColors.white,
                      ),
                      onPressed: () {
                        controller.openMapsToStep(step);
                      },
                    ),
                  ),
                ],
              ),
            ],
          ),
          SizedBox(height: 10),
          Text(
            'Observações:',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            null == controller.delivery.publicText
                ? '-'
                : controller.delivery.publicText!,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 10),
          Text(
            'Outras informações',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            null == controller.delivery.additionalInfo
                ? '-'
                : controller.delivery.additionalInfo!,
            softWrap: true,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 10),
          Text(
            'Taxa de entrega:',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontWeight: FontWeight.bold,
            ),
          ),
          Text(
            controller.delivery.formattedTotalPaid,
            style: TextStyle(
              color: CustomColors.darkGrey,
            ),
          ),
          SizedBox(height: 20),
          Container(
            width: double.infinity,
            child: FlatButton(
              onPressed: controller.loading
                  ? null
                  : () {
                      if (controller.delivery.receiptConfirmation ==
                          ReceiptConfirmation.DISABLED) {
                        controller.confirm();
                        return;
                      }

                      showMaterialModalBottomSheet(
                        // enableDrag: false,
                        // isDismissible: false,
                        context: context,
                        builder: _confirmModal,
                      );
                    },
              height: 50,
              color: Color(0xFF42AA52),
              disabledColor: Color(0xFF42AA52).withOpacity(0.5),
              shape: StadiumBorder(),
              padding: EdgeInsets.symmetric(
                vertical: 10,
                horizontal: 20,
              ),
              child: controller.loading
                  ? SpinKitThreeBounce(
                      color: Colors.white,
                      size: 15,
                    )
                  : Text(
                      'Entregue ao cliente',
                      style: TextStyle(
                        color: CustomColors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _stepReturn(BuildContext context) {
    Delivery.Step step = controller.delivery.steps[0];

    return Container(
      width: double.infinity,
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
            'Solicitado às ${controller.delivery.createdAt.hour}:${controller.delivery.createdAt.minute} de ${controller.delivery.createdAt.day}/${controller.delivery.createdAt.month}',
            style: TextStyle(
              color: CustomColors.darkGrey,
              fontSize: 16,
            ),
          ),
          SizedBox(height: 20),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Flexible(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      controller.delivery.user.name,
                      style: TextStyle(
                        color: CustomColors.orange,
                        fontSize: 18,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    SizedBox(height: 10),
                    Text(
                      step.formattedAddress,
                      style: TextStyle(
                        color: CustomColors.darkGrey,
                      ),
                    ),
                  ],
                ),
              ),
              Row(
                children: [
                  Container(
                    margin: EdgeInsets.only(left: 20),
                    decoration: BoxDecoration(
                      color: Colors.grey,
                      borderRadius: BorderRadius.circular(100),
                    ),
                    child: IconButton(
                      icon: Icon(
                        Icons.copy,
                        color: CustomColors.white,
                      ),
                      onPressed: () {
                        controller.copyStepAddress(step);
                      },
                    ),
                  ),
                  Container(
                    margin: EdgeInsets.only(left: 10),
                    decoration: BoxDecoration(
                      color: CustomColors.orange,
                      borderRadius: BorderRadius.circular(100),
                    ),
                    child: IconButton(
                      icon: Icon(
                        Icons.directions,
                        color: CustomColors.white,
                      ),
                      onPressed: () {
                        controller.openMapsToStep(step);
                      },
                    ),
                  ),
                ],
              ),
            ],
          ),
          SizedBox(height: 20),
          Container(
            width: double.infinity,
            child: FlatButton(
              onPressed: controller.loading ? null : controller.complete,
              height: 50,
              color: Color(0xFF42AA52),
              disabledColor: Color(0xFF42AA52).withOpacity(0.5),
              shape: StadiumBorder(),
              padding: EdgeInsets.symmetric(
                vertical: 10,
                horizontal: 20,
              ),
              child: controller.loading
                  ? SpinKitThreeBounce(
                      color: Colors.white,
                      size: 15,
                    )
                  : Text(
                      'Finalizar entrega',
                      style: TextStyle(
                        color: CustomColors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _confirmModal(BuildContext context) {
    return AnimatedPadding(
      duration: Duration(milliseconds: 250),
      padding: EdgeInsets.only(
        bottom: MediaQuery.of(context).viewInsets.bottom,
      ),
      child: SafeArea(
        // top: false,
        child: SingleChildScrollView(
          child: Container(
            padding: EdgeInsets.only(
              left: 20,
              right: 20,
              bottom: 20,
            ),
            child: Form(
              key: controller.formKey,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    'CONFIRMAÇÃO DE ENTREGA',
                    style: TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 18,
                    ),
                  ),
                  SizedBox(height: 20),
                  TextFormField(
                    controller: controller.nameField,
                    validator: (value) {
                      if (controller.delivery.receiptConfirmation ==
                          ReceiptConfirmation.OPTIONAL) {
                        return null;
                      }

                      if (null == value || value.isEmpty) {
                        return 'Campo obrigatório';
                      }

                      return null;
                    },
                    decoration: InputDecoration(
                      labelText: 'Entregue para (nome completo)',
                      labelStyle: TextStyle(
                        color: CustomColors.black.withOpacity(0.5),
                        fontSize: 18,
                      ),
                      border: OutlineInputBorder(),
                      enabledBorder: OutlineInputBorder(
                        borderSide: BorderSide(
                          color: CustomColors.black.withOpacity(0.5),
                        ),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderSide: BorderSide(
                          color: CustomColors.black.withOpacity(0.5),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 20),
                  TextFormField(
                    controller: controller.docField,
                    validator: (value) {
                      if (controller.delivery.receiptConfirmation ==
                          ReceiptConfirmation.OPTIONAL) {
                        return null;
                      }

                      if (null == value || value.isEmpty) {
                        return 'Campo obrigatório';
                      }

                      return null;
                    },
                    decoration: InputDecoration(
                      labelText: 'Documento (RG ou CPF de quem recebeu)',
                      labelStyle: TextStyle(
                        color: CustomColors.black.withOpacity(0.5),
                        fontSize: 18,
                      ),
                      border: OutlineInputBorder(),
                      enabledBorder: OutlineInputBorder(
                        borderSide: BorderSide(
                          color: CustomColors.black.withOpacity(0.5),
                        ),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderSide: BorderSide(
                          color: CustomColors.black.withOpacity(0.5),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 20),
                  Obx(() {
                    if (null == controller.photo) {
                      return Container(
                        width: double.infinity,
                        child: FlatButton(
                          onPressed: controller.addImage,
                          height: 50,
                          color: Colors.grey[200],
                          shape: StadiumBorder(),
                          padding: EdgeInsets.symmetric(
                            vertical: 10,
                            horizontal: 20,
                          ),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Text(
                                (controller.delivery.receiptConfirmation ==
                                            ReceiptConfirmation.OPTIONAL ||
                                        controller
                                                .delivery.receiptConfirmation ==
                                            ReceiptConfirmation
                                                .REQUIRED_PARTIAL)
                                    ? 'Adicionar foto (opcional)'
                                    : 'Adicionar foto (obrigatório)',
                                style: TextStyle(
                                  color: CustomColors.black,
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              SizedBox(width: 20),
                              Icon(Icons.cloud_upload),
                            ],
                          ),
                        ),
                      );
                    }

                    return Column(
                      children: [
                        Container(
                          child: FutureBuilder<Uint8List?>(
                            future: controller.photoPreview,
                            builder: (context, snapshot) {
                              if (null == snapshot.data) {
                                return SizedBox();
                              }

                              return Image.memory(
                                snapshot.data!,
                                height: 200,
                              );
                            },
                          ),
                        ),
                        SizedBox(height: 20),
                        Container(
                          width: double.infinity,
                          child: FlatButton(
                            onPressed: controller.loading
                                ? null
                                : controller.removeImage,
                            height: 50,
                            color: CustomColors.red,
                            disabledColor: CustomColors.red.withOpacity(0.5),
                            shape: StadiumBorder(),
                            padding: EdgeInsets.symmetric(
                              vertical: 10,
                              horizontal: 20,
                            ),
                            child: Text(
                              'Remover foto',
                              style: TextStyle(
                                color: CustomColors.white,
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                        ),
                      ],
                    );
                  }),
                  SizedBox(height: 20),
                  Obx(() {
                    return Container(
                      width: double.infinity,
                      child: FlatButton(
                        onPressed:
                            controller.loading ? null : controller.confirm,
                        height: 50,
                        color: Color(0xFF42AA52),
                        disabledColor: Color(0xFF42AA52).withOpacity(0.5),
                        shape: StadiumBorder(),
                        padding: EdgeInsets.symmetric(
                          vertical: 10,
                          horizontal: 20,
                        ),
                        child: controller.loading
                            ? SpinKitThreeBounce(
                                color: Colors.white,
                                size: 15,
                              )
                            : Text(
                                'Confirmar',
                                style: TextStyle(
                                  color: CustomColors.white,
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                      ),
                    );
                  }),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
