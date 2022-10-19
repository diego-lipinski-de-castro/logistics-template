import 'package:delivery/utils/colors.dart';
import 'package:flutter/material.dart';
import 'package:get/get_navigation/src/extension_navigation.dart';
import 'package:get/instance_manager.dart';

class CustomSwitch extends StatefulWidget {
  final bool value;
  final VoidCallback onTap;
  final bool enabled;

  const CustomSwitch({
    Key? key,
    required this.value,
    required this.onTap,
    this.enabled = true,
  }) : super(key: key);

  @override
  CustomSwitchState createState() => CustomSwitchState();
}

class CustomSwitchState extends State<CustomSwitch> {
  @override
  Widget build(BuildContext context) {
    return Center(
      child: GestureDetector(
        onTap: widget.enabled
            ? widget.onTap
            : () {
                Get.rawSnackbar(
                  title: 'Você não pode ficar online no momento.',
                  message:
                      'Entre em contato com o suporte para mais informações.',
                  duration: Duration(seconds: 5),
                );
              },
        child: AnimatedContainer(
          duration: Duration(milliseconds: 200),
          width: 100,
          height: 30,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(50),
            color: widget.value
                ? CustomColors.green
                : CustomColors.black.withOpacity(0.3),
          ),
          padding: EdgeInsets.all(5.0),
          child: Stack(
            alignment: Alignment.center,
            children: <Widget>[
              AnimatedPositioned(
                duration: Duration(milliseconds: 200),
                left: widget.value ? 70 : 0,
                child: Container(
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(50),
                    color: Colors.white,
                  ),
                  width: 20,
                  height: 20,
                ),
              ),
              AnimatedPositioned(
                duration: Duration(milliseconds: 200),
                left: widget.value ? 5 : 25,
                child: AnimatedCrossFade(
                  firstChild: Text(
                    'Online',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 20,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  secondChild: Text(
                    'Offline',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 20,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  crossFadeState: widget.value
                      ? CrossFadeState.showFirst
                      : CrossFadeState.showSecond,
                  duration: Duration(milliseconds: 200),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
