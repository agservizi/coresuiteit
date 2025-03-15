import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"

export default function RecentSales() {
  return (
    <div className="space-y-6">
      <div className="flex items-center gap-4 rounded-lg p-2 transition-all hover:bg-muted/50">
        <Avatar className="h-10 w-10 border-2 border-primary/10">
          <AvatarImage src="/placeholder.svg?height=36&width=36" alt="Avatar" />
          <AvatarFallback className="bg-primary/10 text-primary">MR</AvatarFallback>
        </Avatar>
        <div className="ml-1 space-y-1 flex-1">
          <p className="text-sm font-medium leading-none">Mario Rossi</p>
          <p className="text-sm text-muted-foreground">mario.rossi@example.com</p>
        </div>
        <div className="text-right">
          <p className="text-sm font-medium text-green-600">+€129.00</p>
          <p className="text-xs text-muted-foreground">Iliad SIM + Accessori</p>
        </div>
      </div>
      <div className="flex items-center gap-4 rounded-lg p-2 transition-all hover:bg-muted/50">
        <Avatar className="h-10 w-10 border-2 border-primary/10">
          <AvatarImage src="/placeholder.svg?height=36&width=36" alt="Avatar" />
          <AvatarFallback className="bg-primary/10 text-primary">LB</AvatarFallback>
        </Avatar>
        <div className="ml-1 space-y-1 flex-1">
          <p className="text-sm font-medium leading-none">Laura Bianchi</p>
          <p className="text-sm text-muted-foreground">laura.bianchi@example.com</p>
        </div>
        <div className="text-right">
          <p className="text-sm font-medium text-green-600">+€399.00</p>
          <p className="text-xs text-muted-foreground">WindTre SIM + Smartphone</p>
        </div>
      </div>
      <div className="flex items-center gap-4 rounded-lg p-2 transition-all hover:bg-muted/50">
        <Avatar className="h-10 w-10 border-2 border-primary/10">
          <AvatarImage src="/placeholder.svg?height=36&width=36" alt="Avatar" />
          <AvatarFallback className="bg-primary/10 text-primary">GV</AvatarFallback>
        </Avatar>
        <div className="ml-1 space-y-1 flex-1">
          <p className="text-sm font-medium leading-none">Giuseppe Verdi</p>
          <p className="text-sm text-muted-foreground">giuseppe.verdi@example.com</p>
        </div>
        <div className="text-right">
          <p className="text-sm font-medium text-green-600">+€89.00</p>
          <p className="text-xs text-muted-foreground">Fastweb SIM</p>
        </div>
      </div>
      <div className="flex items-center gap-4 rounded-lg p-2 transition-all hover:bg-muted/50">
        <Avatar className="h-10 w-10 border-2 border-primary/10">
          <AvatarImage src="/placeholder.svg?height=36&width=36" alt="Avatar" />
          <AvatarFallback className="bg-primary/10 text-primary">FC</AvatarFallback>
        </Avatar>
        <div className="ml-1 space-y-1 flex-1">
          <p className="text-sm font-medium leading-none">Francesca Conti</p>
          <p className="text-sm text-muted-foreground">francesca.conti@example.com</p>
        </div>
        <div className="text-right">
          <p className="text-sm font-medium text-green-600">+€249.00</p>
          <p className="text-xs text-muted-foreground">Sky TV + Sky Wifi</p>
        </div>
      </div>
      <div className="flex items-center gap-4 rounded-lg p-2 transition-all hover:bg-muted/50">
        <Avatar className="h-10 w-10 border-2 border-primary/10">
          <AvatarImage src="/placeholder.svg?height=36&width=36" alt="Avatar" />
          <AvatarFallback className="bg-primary/10 text-primary">AM</AvatarFallback>
        </Avatar>
        <div className="ml-1 space-y-1 flex-1">
          <p className="text-sm font-medium leading-none">Antonio Marino</p>
          <p className="text-sm text-muted-foreground">antonio.marino@example.com</p>
        </div>
        <div className="text-right">
          <p className="text-sm font-medium text-green-600">+€179.00</p>
          <p className="text-xs text-muted-foreground">Pianeta Fibra + Accessori</p>
        </div>
      </div>
    </div>
  )
}

