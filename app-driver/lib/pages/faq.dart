import 'package:delivery/widgets/drawer.dart';
import 'package:flutter/material.dart';

class FaqPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Ajuda e suporte'),
        centerTitle: true,
      ),
      drawer: CustomDrawer(),
      body: Container(),
    );
  }
}
