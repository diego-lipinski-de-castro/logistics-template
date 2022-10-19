import 'package:delivery/models/delivery.dart';
import 'package:delivery/repositories/delivery.dart';
import 'package:flutter/widgets.dart';
import 'package:get/get.dart';

class TodaysController extends GetxController {
  final DeliveryRepository deliveryRepository;

  final _scrollController = ScrollController();
  ScrollController get scrollController => _scrollController;

  final _deliveries = Rx<List<Delivery>>([]);
  List<Delivery> get deliveries => _deliveries.value;

  int page = 1;
  bool hasMore = true;

  final _loadingMore = RxBool(false);
  bool get loadingMore => _loadingMore.value;

  final _loading = RxBool(true);
  bool get loading => _loading.value;

  TodaysController({required this.deliveryRepository});

  @override
  void onInit() {
    super.onInit();

    scrollController.addListener(_scrollListener);

    _init();
  }

  @override
  void onClose() {
    scrollController.removeListener(_scrollListener);

    super.onClose();
  }

  _scrollListener() {
    if (_scrollController.position.extentAfter < 300) {
      _loadMore();
    }
  }

  _init() async {
    _loading.value = true;

    if (!hasMore) {
      hasMore = true;
    }

    page = 1;

    _deliveries.update((list) {
      list = [];
    });

    List<Delivery> deliveries = await deliveryRepository.today();

    if (deliveries.isEmpty) {
      hasMore = false;
      _loading.value = false;
      return;
    }

    _deliveries.update((list) {
      list!.addAll(deliveries);
    });

    _loading.value = false;
  }

  _loadMore() async {
    if (loadingMore || !hasMore) return;

    _loadingMore.value = true;

    page++;

    List<Delivery> deliveries = await deliveryRepository.today(page);

    if (deliveries.isEmpty) {
      hasMore = false;
      _loadingMore.value = false;
      return;
    }

    _deliveries.update((list) {
      list!.addAll(deliveries);
    });

    _loadingMore.value = false;
  }
}
