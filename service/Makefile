gen-proto:
	protoc  --php_out=protobuf/build  --php-grpc_out=./protobuf/build  --plugin=protoc-gen-php-grpc ./protobuf/src/*.proto
server:
	php -S localhost:8000 -t public
grpc:
	./rr-grpc serve -v -d