import 'dart:io';
import 'package:delivery/models/driver.dart';
import 'package:delivery/utils/colors.dart';
import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';

class UploadWidget extends StatelessWidget {
  final String submitLabel;
  final String label;
  final Function(File) onFileSelected;
  final VoidCallback onRemove;
  final File? file;
  final Document? document;

  const UploadWidget({
    Key? key,
    required this.submitLabel,
    required this.label,
    required this.onFileSelected,
    required this.onRemove,
    this.file,
    this.document,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () async {
        FilePickerResult? result = await FilePicker.platform.pickFiles(
          // type: FileType.image,
          withData: true,
        );

        if (null != result) {
          Future.delayed(Duration(milliseconds: 500), () {
            onFileSelected(
              File(result.files.single.path!),
            );
          });
        }
      },
      child: Stack(
        children: [
          ConstrainedBox(
            constraints: BoxConstraints(
              maxWidth: double.infinity,
              maxHeight: double.infinity,
            ),
            child: AnimatedContainer(
              duration: Duration(milliseconds: 200),
              curve: Curves.bounceInOut,
              width: double.infinity,
              height: (file != null || document != null)
                  ? MediaQuery.of(context).size.width
                  : 200,
              decoration: BoxDecoration(
                color: CustomColors.black.withOpacity(0.1),
                borderRadius: BorderRadius.circular(5),
              ),
              child: (file != null || document != null)
                  ? ClipRRect(
                      borderRadius: BorderRadius.circular(5),
                      child: (file != null)
                          ? Image.file(
                              file!,
                              fit: BoxFit.fill,
                            )
                          : Image.network(
                              document!.fullUrl!,
                              fit: BoxFit.fill,
                            ),
                    )
                  : Center(
                      child: Padding(
                        padding: EdgeInsets.symmetric(
                          vertical: 50,
                        ),
                        child: Text(
                          submitLabel,
                          style: TextStyle(
                            color: CustomColors.black,
                            fontWeight: FontWeight.bold,
                            fontSize: 18,
                          ),
                        ),
                      ),
                    ),
            ),
          ),
          if (file != null || document != null) ...[
            Positioned(
              left: 0,
              right: 0,
              top: 0,
              child: ClipRRect(
                borderRadius: BorderRadius.only(
                  topLeft: Radius.circular(5),
                  topRight: Radius.circular(5),
                ),
                child: Container(
                  decoration: BoxDecoration(
                    color: CustomColors.black.withOpacity(0.5),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Padding(
                        padding: EdgeInsets.only(left: 15),
                        child: Text(
                          label,
                          style: TextStyle(
                            color: CustomColors.white,
                            fontSize: 18,
                          ),
                        ),
                      ),
                      Row(
                        children: [
                          Container(
                            padding: EdgeInsets.all(15),
                            child: Icon(
                              Icons.edit,
                              color: CustomColors.white,
                            ),
                          ),
                          if (document == null) ...[
                            GestureDetector(
                              onTap: onRemove,
                              child: Container(
                                padding: EdgeInsets.all(15),
                                child: Icon(
                                  Icons.close,
                                  color: CustomColors.white,
                                ),
                              ),
                            ),
                          ],
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ],
      ),
    );
  }
}
